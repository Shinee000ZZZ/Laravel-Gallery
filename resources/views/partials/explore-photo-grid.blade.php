@forelse ($photos as $photo)
    @if ($photo->image_path)
        <div class="photo-item relative group">
            <a href=""
                class="block break-inside-avoid overflow-hidden rounded-lg hover:brightness-75 transition duration-200">
                <img class="w-full h-auto object-cover hover:scale-105 ease-in-out duration-200 lazy"
                    data-src="{{ asset('storage/' . $photo->image_path) }}"
                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                    alt="Photo: {{ $photo->title ?? '' }}">
            </a>
        </div>
    @endif
@empty
    <div class="col-span-4 text-center text-gray-500">
        Tidak ada foto yang tersedia.
    </div>
@endforelse
