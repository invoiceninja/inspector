<input 
    name="{{ $column['name'] }}" 
    value="0" 
    type="hidden" />

<input 
    name="{{ $column['name'] }}" 
    value="1" 
    type="checkbox"
    {{ $value ? 'checked' : '' }} />