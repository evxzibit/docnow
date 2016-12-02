<!-- Internet Explorer toolbox component -->
<table border="0" cellpadding="0" cellspacing="2">
<tr>
<td><? echo DefaultBullet; ?></td>
<td><a href="#" onclick="window.external.addFavorite (window.location, document.title); return false">Add to favorites</a></td>
</tr>
<tr>
<td><? echo DefaultBullet; ?></td>
<td><a href="#" onclick="this.style.behavior='url(#default#homepage)'; this.setHomePage ('<? echo ThisURL; ?>'); return false">Make my homepage</a></td>
</tr>
</table>