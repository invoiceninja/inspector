<input 
    name="{{ $column['name'] }}" 
    value="{{ date('Y-m-d\TH:i', \strtotime($value)) }}" 
    type="datetime-local" />
