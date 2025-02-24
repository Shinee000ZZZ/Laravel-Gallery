@forelse ($photos as $photo)
    @if ($photo->image_path)
        <div class="photo-item relative group">
            <a href="{{ route('photos.show', $photo->photo_id) }}" class="block aspect-square overflow-hidden">
                <img class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105 lazy"
                    data-src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo: {{ $photo->title ?? '' }}">

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

            <!-- Three-dot Menu -->
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <div class="relative">
                    <button id="photo-menu-{{ $photo->photo_id }}"
                        class="bg-white/20 hover:bg-white/40 text-white px-2 rounded-full"
                        onclick="togglePhotoMenu({{ $photo->photo_id }})">
                        <i class='bx bx-dots-horizontal-rounded text-xl'></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="photo-dropdown-{{ $photo->photo_id }}"
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-10">
                        <ul class="py-1 text-sm">
                            <li>
                                <a href="{{ route('photos.edit', $photo->photo_id) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                                    <i class='bx bx-edit'></i> Edit Foto
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('photos.trash', $photo->photo_id) }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                                        <i class='bx bx-trash' style="color:#ff0000"></i> Buang ke Sampah
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
@empty
    <div class="col-span-4 text-center text-gray-500">
        Tidak ada foto yang tersedia.
    </div>
@endforelse
