<input 
    name="{{ $column->getName() }}" 
    value="{{ $value ?? date('Y-m-d', \strtotime($value)) }}" 
    type="date" />
