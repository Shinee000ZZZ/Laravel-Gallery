@forelse ($photos as $photo)
    <div class="photo-item relative group" data-photo-id="{{ $photo->photo_id }}"
        data-likes-count="{{ $photo->likes_count }}" data-comments-count="{{ $photo->comments_count }}">
        <a href="{{ route('photos.show', $photo->photo_id) }}" class="block aspect-square overflow-hidden">
            <img class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105 lazy"
                data-src="{{ asset('storage/' . $photo->image_path) }}"
                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 10' opacity='0.1'%3E%3Crect width='10' height='10' fill='%23cccccc'/%3E%3C/svg%3E"
                alt="Photo: {{ $photo->title ?? '' }}">

            <!-- Hover Overlay untuk likes & comments -->
            <div
                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-8 text-white">
                <div class="flex items-center gap-2">
                    <i class="bx bxs-heart text-2xl"></i>
                    <span>{{ $photo->likes_count }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="bx bxs-comment text-2xl"></i>
                    <span>{{ $photo->comments_count }}</span>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-span-4 text-center text-gray-500">
        Tidak ada foto yang tersedia.
    </div>
@endforelse
