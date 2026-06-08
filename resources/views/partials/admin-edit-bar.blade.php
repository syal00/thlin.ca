<div class="inline-edit-bar" data-inline-edit-bar>
    <div class="container inline-edit-bar__inner">
        <span class="inline-edit-bar__message">Admin Mode — click editable text or images to update content.</span>
        <div class="inline-edit-bar__actions">
            <button type="button" class="inline-edit-bar__btn inline-edit-bar__btn--toggle" data-edit-toggle>Enable Edit Mode</button>
            <a href="{{ route('admin.dashboard') }}" class="inline-edit-bar__btn inline-edit-bar__btn--link">Admin Dashboard</a>
            <form action="{{ route('admin.logout') }}" method="post" class="inline-edit-bar__logout">
                @csrf
                <button type="submit" class="inline-edit-bar__btn inline-edit-bar__btn--link">Log out</button>
            </form>
        </div>
    </div>
    <div class="inline-edit-toast" data-inline-toast hidden></div>
</div>
<input type="file" accept="image/jpeg,image/jpg,image/png,image/webp" data-inline-image-input hidden>
