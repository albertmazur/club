<footer class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3 border-top px-4">
    <div class="d-flex align-items-center mb-3 mb-md-0">
        <a href="https://albertmazur.pl" target="_blank" class="text-body-secondary text-decoration-none">© 2025 Albert Mazur</a>
    </div>
        <nav class="nav justify-content-center mb-3 mb-md-0">
        <a class="nav-link px-2 text-body-secondary" href="{{ route('event.index') }}">{{ __('app.events') }}</a>
        <a class="nav-link px-2 text-body-secondary" href="{{ route('stadium.list') }}">{{ __('app.club_facilities') }}</a>
        <a class="nav-link px-2 text-body-secondary" href="{{ route('about') }}">{{ __('app.about.title') }}</a>
        <a class="nav-link px-2 text-body-secondary" href="{{ route('contact') }}">{{ __('app.contact.title') }}</a>
    </nav>
    <ul class="list-unstyled d-flex mb-0">
        <li class="ms-3">
            <a href="https://x.com" target="_blank" class="text-body-secondary fs-4" aria-label="{{ __('app.social_media.x') }}">
                <i class="bi bi-twitter-x"></i>
            </a>
        </li>
        <li class="ms-3">
            <a href="https://www.instagram.com" target="_blank" class="text-body-secondary fs-4" aria-label="{{ __('app.social_media.instagram') }}">
                <i class="bi bi-instagram"></i>
            </a>
        </li>
        <li class="ms-3">
            <a href="https://www.facebook.com" target="_blank" class="text-body-secondary fs-4" aria-label="{{ __('app.social_media.facebook') }}">
                <i class="bi bi-facebook"></i>
            </a>
        </li>
    </ul>
</footer>