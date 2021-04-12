<x-sendportal.text-field name="from_name" :label="__('From Name')" :value="$email->from_name ?? old('from_name')" />
<x-sendportal.text-field name="from_email" :label="__('From Email')" type="email" :value="$email->from_email ?? old('from_email')" />
