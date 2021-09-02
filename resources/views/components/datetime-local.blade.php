<input 
    name="{{ $column->getName() }}" 
    value="{{ date('Y-m-d\TH:i', \strtotime($value)) }}" 
    type="datetime-local" />
