<div class="inline-edit-bar" data-inline-edit-bar role="region" aria-label="Website editor">
    <div class="inline-edit-bar__accent" aria-hidden="true"></div>
    <div class="inline-edit-bar__inner">
        <p class="inline-edit-bar__message" data-inline-edit-status role="status" aria-live="polite">
            You are viewing the website as an administrator.
        </p>
        <div class="inline-edit-bar__actions" role="toolbar" aria-label="Editing actions">
            <button type="button" id="enable-inline-editing"
                    class="inline-edit-bar__btn inline-edit-bar__btn--primary"
                    data-edit-enable aria-pressed="false">
                Enable Inline Editing
            </button>
            <button type="button" id="disable-inline-editing"
                    class="inline-edit-bar__btn inline-edit-bar__btn--secondary"
                    data-edit-disable aria-pressed="false" hidden>
                Disable Inline Editing
            </button>
            @isset($cmsPage)
                <a href="{{ route('admin.pages.edit', $cmsPage) }}"
                   class="inline-edit-bar__btn inline-edit-bar__btn--cms" data-inline-edit-safe>
                    Edit This Page in CMS
                </a>
            @endisset
            <a href="{{ route('admin.dashboard') }}"
               class="inline-edit-bar__btn inline-edit-bar__btn--link" data-inline-edit-safe>
                Open CMS Dashboard
            </a>
            <form action="{{ route('admin.logout') }}" method="post" class="inline-edit-bar__logout">
                @csrf
                <button type="submit" class="inline-edit-bar__btn inline-edit-bar__btn--link" data-inline-edit-safe>Log out</button>
            </form>
        </div>
    </div>
    <div class="inline-edit-toast" data-inline-toast role="alert" aria-live="assertive" hidden></div>
</div>
<input type="file" accept="image/jpeg,image/jpg,image/png,image/webp" data-inline-image-input hidden>
