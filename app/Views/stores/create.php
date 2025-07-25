<h1>Create Store</h1>
<form class="form-grid" method="post" action="/stores/store">
    <div class="form-group">
        <?= $this->csrfField() ?>
        <label for="name">Name:</label>
        <input id="name" name="name" required>

        <label for="slug">Slug:</label>
        <input id="slug" name="slug" required>

        <label for="address_line1">Address Line 1:</label>
        <input id="address_line1" name="address_line1" required>

        <label for="address_line2">Address Line 2:</label>
        <input id="address_line2" name="address_line2">

        <label for="city">City:</label>
        <input id="city" name="city" required>

        <label for="state_region">State/Region:</label>
        <input id="state_region" name="state_region">

        <label for="country">Country:</label>
        <input id="country" name="country">

        <label for="phone">Phone:</label>
        <input id="phone" name="phone">

        <label for="email">Email:</label>
        <input id="email" name="email">

        <button type="submit">Save</button>
    </div>
</form>