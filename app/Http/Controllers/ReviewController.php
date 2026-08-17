<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $customer = Auth::guard('web')->user();

        $data = $request->validate([
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'comment'        => ['nullable', 'string', 'max:1000'],
            'order_item_id'  => ['required', 'integer'],
            'photos'         => ['nullable', 'array', 'max:5'],
            'photos.*'       => ['image', 'max:5120'],
        ]);

        $orderItem = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.id', $data['order_item_id'])
            ->where('order_items.product_id', $productId)
            ->where('orders.customer_id', $customer->id)
            ->where('orders.order_status', 'delivered')
            ->first();

        if (! $orderItem) {
            return back()->withErrors(['error' => 'You can only review products from delivered orders.']);
        }

        $existing = Review::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'You have already reviewed this product.']);
        }

        $review = Review::create([
            'customer_id'   => $customer->id,
            'product_id'    => $productId,
            'order_item_id' => $data['order_item_id'],
            'rating'        => $data['rating'],
            'comment'       => $data['comment'] ?? null,
            'is_visible'    => true,
        ]);

        if (! empty($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $url = $this->storeReviewPhoto($photo);
                if ($url) {
                    ReviewPhoto::create([
                        'review_id'  => $review->id,
                        'image_url'  => $url,
                    ]);
                }
            }
        }

        NotificationService::sendToAdmins(
            'new_review',
            'New review for '.$review->product->name,
            $customer->full_name.' gave '.$data['rating'].' star(s).',
            'reviews'
        );

        return back()->with('message', 'Review submitted successfully!');
    }

    public function update(Request $request, int $id)
    {
        $customer = Auth::guard('web')->user();
        $review = Review::where('id', $id)->where('customer_id', $customer->id)->first();

        if (! $review) {
            return back()->withErrors(['error' => 'Review not found.']);
        }

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update([
            'rating'  => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        if (! empty($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $url = $this->storeReviewPhoto($photo);
                if ($url) {
                    ReviewPhoto::create([
                        'review_id'  => $review->id,
                        'image_url'  => $url,
                    ]);
                }
            }
        }

        return back()->with('message', 'Review updated successfully!');
    }

    public function destroy(int $id)
    {
        $customer = Auth::guard('web')->user();
        $review = Review::where('id', $id)->where('customer_id', $customer->id)->first();

        if (! $review) {
            return back()->withErrors(['error' => 'Review not found.']);
        }

        foreach ($review->photos as $photo) {
            $path = public_path('images/'.$photo->image_url);
            if (file_exists($path)) {
                unlink($path);
            }
            $photo->delete();
        }

        $review->delete();

        return back()->with('message', 'Review deleted.');
    }

    public function deletePhoto(int $id)
    {
        $customer = Auth::guard('web')->user();
        $photo = ReviewPhoto::findOrFail($id);
        $review = Review::where('id', $photo->review_id)->where('customer_id', $customer->id)->first();

        if (! $review) {
            return back()->withErrors(['error' => 'Photo not found.']);
        }

        $path = public_path('images/'.$photo->image_url);
        if (file_exists($path)) {
            unlink($path);
        }

        $photo->delete();

        return back()->with('message', 'Photo removed.');
    }

    private function storeReviewPhoto($file): ?string
    {
        if (! $file || ! $file->isValid()) {
            return null;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, $allowed)) {
            return null;
        }

        $filename = 'review_'.time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
        $file->move(public_path('images'), $filename);

        return $filename;
    }
}
