<input 
    name="{{ $column->getName() }}" 
    value="0" 
    type="hidden" />

<input 
    name="{{ $column->getName() }}" 
    value="1" 
    type="checkbox"
    {{ $value ? 'checked' : '' }} />