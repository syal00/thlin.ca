<div class="admin-inline-help-content">
    <p>Inline editing now supports:</p>
    <ul class="admin-inline-list">
        <li><strong>Simple text editing</strong> for headings, buttons, and navigation labels (Save / Cancel only).</li>
        <li><strong>Rich text editing</strong> for paragraphs and content areas with a floating WYSIWYG toolbar.</li>
        <li>Formatting tools including bold, italic, underline, strikethrough, lists, alignment, links, tables, colors, character map, code view, search/replace, visual blocks, and fullscreen for long content.</li>
        <li>Save / Cancel controls, keyboard shortcuts (Ctrl/Cmd+S to save, Escape to cancel), and unsaved-changes warning when leaving the page.</li>
    </ul>
    <p>For page URL, parent page, publishing, file uploads, and full page management, use the <strong>CMS Pages</strong> section.</p>
    <ol class="admin-steps-list">
        <li>Open the public website while logged in as an admin.</li>
        <li>Click <strong>Enable Inline Editing</strong> in the admin bar, or use the link below to start with editing enabled.</li>
        <li>Click highlighted text (orange dashed outline).</li>
        <li>Make your change and click <strong>Save</strong>. The toolbar stays above or below the content so it does not cover your text.</li>
        <li>Use <strong>Edit This Page in CMS</strong> for page structure, files, drafts, and navigation settings.</li>
    </ol>
</div>

<div class="admin-grid-two admin-inline-help-grid">
    <div class="admin-card admin-card--compact">
        <h3>What you can edit inline</h3>
        <ul class="admin-inline-list">
            <li><strong>Pages</strong> — hero title (simple), hero subtitle and body (rich text), navigation labels (simple)</li>
            <li><strong>Homepage</strong> — section headings (simple), paragraphs and CTA text (rich text) via site settings</li>
            <li><strong>Site settings</strong> — navigation labels, footer text, global and page CTAs, contact form and office details</li>
            <li><strong>News</strong> — post title (simple), excerpt and body (rich text)</li>
            <li><strong>Careers</strong> — job title, location, employment type (simple), job description body (rich text)</li>
            <li><strong>Board</strong> — member name and role (simple), bio (rich text)</li>
            <li><strong>Portfolio</strong> — project title (simple), excerpt (rich text), and project images</li>
        </ul>
    </div>

    <div class="admin-card admin-card--compact">
        <h3>Edit in CMS Pages instead</h3>
        <ul class="admin-inline-list">
            <li>Page URL and parent page</li>
            <li>Draft / publish status</li>
            <li>Website menu visibility and order</li>
            <li>PDF and file uploads</li>
            <li>Navigation structure and custom page templates</li>
            <li>News publish dates, locations, and new posts</li>
            <li>Career posting dates and new job listings</li>
            <li>Board member photos and new members</li>
        </ul>
        <div class="form-actions">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-light btn-sm">Go to Pages</a>
        </div>
    </div>
</div>
