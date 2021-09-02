<input 
    name="{{ $column->getName() }}" 
    value="{{ $value }}" 
    type="checkbox"
    {{ $value ? 'checked' : '' }} />