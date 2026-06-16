<div class="inline-edit-bar" data-inline-edit-bar>
    <div class="inline-edit-bar__inner">
        <span class="inline-edit-bar__message">You are viewing the website as an admin.</span>
        <div class="inline-edit-bar__actions">
            <button type="button"
                    id="enable-inline-editing"
                    class="inline-edit-bar__btn inline-edit-bar__btn--primary"
                    data-edit-enable>
                Enable Inline Editing
            </button>
            <button type="button"
                    id="disable-inline-editing"
                    class="inline-edit-bar__btn inline-edit-bar__btn--secondary"
                    data-edit-disable
                    hidden>
                Disable Inline Editing
            </button>
            @isset($cmsPage)
                <a href="{{ route('admin.pages.edit', $cmsPage) }}" class="inline-edit-bar__btn inline-edit-bar__btn--cms">
                    Edit This Page in CMS
                </a>
            @endisset
            <a href="{{ route('admin.dashboard') }}" class="inline-edit-bar__btn inline-edit-bar__btn--link">
                Open CMS Dashboard
            </a>
            <form action="{{ route('admin.logout') }}" method="post" class="inline-edit-bar__logout">
                @csrf
                <button type="submit" class="inline-edit-bar__btn inline-edit-bar__btn--link">Log out</button>
            </form>
        </div>
    </div>
    <div class="inline-edit-toast" data-inline-toast hidden></div>
</div>
<input type="file" accept="image/jpeg,image/jpg,image/png,image/webp" data-inline-image-input hidden>
