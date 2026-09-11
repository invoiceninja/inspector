<input 
    name="{{ $column['name'] }}" 
    value="{{ $value ?? date('Y-m-d', \strtotime($value)) }}" 
    type="date" />
