@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('public/img/Logo_enternalux_11zon.png') }}" class="logo" />
            <br />
            {{ $slot }}
        </a>
    </td>
</tr>