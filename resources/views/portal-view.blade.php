@extends($layout)

@section($section)
@livewire($component, $componentParameters, $component . rand(1000, 1000000))
@endsection