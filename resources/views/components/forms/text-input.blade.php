@php($id = Str::uuid())
@props(['name'=>'input-name', 'label'=>null])
<div class="relative {{$errors->has($name) ? 'mb-6':'mb-3' }}">
    <input {{$attributes
        ->class([
            "_is-error"=>$errors->has($name),
            "w-full h-14 px-4 rounded-lg border border-[#A07BF0] bg-white/20 focus:border-pink focus:shadow-[0_0_0_2px_#EC4176] outline-none transition text-white placeholder:text-white text-xxs md:text-xs font-semibold"
        ])}}
           name="{{$name}}"
           value="{{old($name)}}"
           id="{{$name.'-'.$id}}"
    />
    @if($label)
        <label
                for="{{$name.'-'.$id}}"
                class=""
        >{{$label}}
        </label>
    @endif
    @error($name)
    <div class="mt-3 text-pink text-xxs xs:text-xs">
        {{$message}}
    </div>
    @enderror
</div>

