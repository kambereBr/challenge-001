<h1>Create Store</h1>
<form method="post" action="/stores/store">
    <?= $this->csrfField() ?>
    <label>Name: <input name="name"></label><br>
    <label>Slug: <input name="slug"></label><br>
    <label>Address Line 1: <input name="address_line1"></label><br>
    <label>Address Line 2: <input name="address_line2"></label><br>
    <label>City: <input name="city"></label><br>
    <label>State/Region: <input name="state_region"></label><br>
    <label>Country: <input name="country"></label><br>
    <label>Phone: <input name="phone"></label><br>
    <label>Email: <input name="email"></label><br>
    <button type="submit">Save</button>
</form>