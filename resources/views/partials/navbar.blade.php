<nav class="border-b border-zinc-200 bg-white">
    <div class="mx-auto flex max-w-3xl items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight">
            dwb<span class="text-zinc-400">.portfolio</span>
        </a>

        <div class="flex items-center gap-1 text-sm">
            @php
            $links = [
                'home' => 'Home',
                'about' => 'About',
                'education' => 'Education',
                'projects.index' => 'Projects',
                'pertemuan8.index' => 'Lab 08',
            ];
            @endphp

            @foreach ($links as $route => $label)
            @php
            $active = $route === 'projects.index'
            ? request()->routeIs('projects.*')
            : request()->routeIs($route);
            @endphp
            <a href="{{ route($route) }}"
                class="rounded-md px-3 py-1.5 transition {{ $active ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</nav>