<div class="rating rating-lg rating-half">
    @for ($i = 0.5; $i <= 5; $i += 0.5)
        <input
            type="radio"
            name="{{$name}}"
            class="mask mask-star-2 {{ $i == intval($i) ? 'mask-half-2' : 'mask-half-1' }} bg-orange-500"
            aria-label="{{ $i }} star"
            value="{{ $i }}"
            {{ $selectedRating == $i ? 'checked="checked"' : '' }}
        />
    @endfor
</div>
