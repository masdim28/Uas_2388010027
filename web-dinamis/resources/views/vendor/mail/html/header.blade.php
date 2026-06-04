@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    <img src="https://raw.githubusercontent.com/masdim28/PPL_Klmpk5/main/public/images/logo_adeafwa.png" class="logo" alt="Ade Afwa Boutique Logo" style="max-height: 75px; margin-bottom: 10px;">
    <br>
    <span style="font-size: 1.5rem; font-weight: 800; color: #1e1b4b;">{{ trim($slot) === 'Laravel' ? 'Ade Afwa Boutique' : $slot }}</span>
</a>
</td>
</tr>
