namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'brand_id' => $this->brand_id,
            'description' => $this->description,
            'sell_price' => $this->sell_price,
            'cost_price' => $this->cost_price,
            'images' => $this->images->map(function ($image) {
                return [
                    'image_path' => asset('storage/images/' . basename($image->image_path))
                ];
            }),
        ];
    }
}
