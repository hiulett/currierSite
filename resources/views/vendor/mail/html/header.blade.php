@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@php($tenantLogo = config('app.tenant_logo_url'))
@if ($tenantLogo)
<img src="{{ $tenantLogo }}" class="logo" alt="{{ config('app.name') }}"
     style="display: block; width: 220px; max-width: 100%; height: auto; max-height: 90px; margin: 0 auto;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
