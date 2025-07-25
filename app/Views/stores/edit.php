<h1>Edit Store</h1>
<form method="post" action="/stores/update/<?= $store->id ?>">
    <?= $this->csrfField() ?>
    <label>Name: <input name="name" value="<?= htmlspecialchars($store->name) ?>"></label><br>
    <label>Slug: <input name="slug" value="<?= htmlspecialchars($store->slug) ?>"></label><br>
    <label>Address Line 1: <input name="address_line1" value="<?= htmlspecialchars($store->address_line1) ?>"></label><br>
    <label>Address Line 2: <input name="address_line2" value="<?= htmlspecialchars($store->address_line2) ?>"></label><br>
    <label>City: <input name="city" value="<?= htmlspecialchars($store->city) ?>"></label><br>
    <label>State/Region: <input name="state_region" value="<?= htmlspecialchars($store->state_region) ?>"></label><br>
    <label>Country: <input name="country" value="<?= htmlspecialchars($store->country) ?>"></label><br>
    <label>Phone: <input name="phone" value="<?= htmlspecialchars($store->phone) ?>"></label><br>
    <label>Email: <input name="email" value="<?= htmlspecialchars($store->email) ?>"></label><br>
    <button type="submit">Update</button>
</form>