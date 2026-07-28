@extends('admin::layouts.shell')

@section('title', 'Dashboard — ' . config('app.name'))

@section('admin_content')
    @php
        $canContent = ! empty($canManageContent);
        $areas = is_array($contentAreas ?? null) ? $contentAreas : [];
        $canArea = static fn (string $key): bool => $canContent || ! empty($areas[$key]);
        $tiles = [];

        if ($canArea('stories')) {
            $tiles[] = [
                'titleDe' => 'Stories',
                'titleEn' => 'Stories',
                'descDe' => 'Playbooks pflegen — von Einzel-Stories bis Serien.',
                'descEn' => 'Maintain playbooks — from standalones to series.',
                'count' => (int) ($storyCount ?? 0),
                'countLabelDe' => 'Stories',
                'countLabelEn' => 'stories',
                'icon' => 'fa-book-open',
                'href' => locale_route('admin.stories.index'),
                'stats' => [
                    ['value' => (int) ($storySeriesCount ?? 0), 'labelDe' => 'Serien', 'labelEn' => 'series'],
                ],
            ];
        }
        if ($canArea('stories') || $canArea('vendorsSources')) {
            $tiles[] = [
                'titleDe' => 'Advisor',
                'titleEn' => 'Advisor',
                'descDe' => 'Kuratierte Empfehlungen für den Governance Advisor.',
                'descEn' => 'Curated recommendations for the Governance Advisor.',
                'count' => (int) ($advisorCount ?? 0),
                'countLabelDe' => 'Empfehlungen',
                'countLabelEn' => 'recommendations',
                'icon' => 'fa-compass',
                'href' => locale_route('admin.advisor.index'),
                'stats' => [
                    ['value' => (int) ($advisorStoryCount ?? 0), 'labelDe' => 'Stories', 'labelEn' => 'stories'],
                    ['value' => (int) ($advisorSeriesCount ?? 0), 'labelDe' => 'Serien', 'labelEn' => 'series'],
                    ['value' => (int) ($advisorSupplierCount ?? 0) + (int) ($advisorVendorCount ?? 0), 'labelDe' => 'Katalog', 'labelEn' => 'catalog'],
                ],
            ];
        }
        if ($canArea('planTemplates')) {
            $tiles[] = [
                'titleDe' => 'Plan-Templates',
                'titleEn' => 'Plan templates',
                'descDe' => 'Sprint-Vorlagen für den Planner.',
                'descEn' => 'Sprint templates for the planner.',
                'count' => (int) ($templateCount ?? 0),
                'countLabelDe' => 'Templates',
                'countLabelEn' => 'templates',
                'icon' => 'fa-table-columns',
                'href' => locale_route('admin.plan-templates.index'),
                'stats' => [],
            ];
        }
        if ($canArea('news')) {
            $tiles[] = [
                'titleDe' => 'Radar / News',
                'titleEn' => 'Radar / News',
                'descDe' => 'Quellen, eigene News und RSS-Feeds für den Governance Radar.',
                'descEn' => 'Sources, own news, and RSS feeds for the Governance Radar.',
                'count' => (int) ($radarSourceCount ?? 0),
                'countLabelDe' => 'Quellen',
                'countLabelEn' => 'sources',
                'icon' => 'fa-satellite-dish',
                'href' => locale_route('admin.radar.index'),
                'stats' => [
                    ['value' => (int) ($radarOwnNewsCount ?? $radarItemCount ?? 0), 'labelDe' => 'Eigene News', 'labelEn' => 'own news'],
                    ['value' => (int) ($radarRssNewsCount ?? 0), 'labelDe' => 'RSS', 'labelEn' => 'RSS'],
                ],
            ];
        }
        if ($canArea('vendorsSources')) {
            $tiles[] = [
                'titleDe' => 'Vendors',
                'titleEn' => 'Vendors',
                'descDe' => 'Vendor-Katalog und Product-Zuordnungen.',
                'descEn' => 'Vendor catalog and product mappings.',
                'count' => (int) ($vendorCount ?? 0),
                'countLabelDe' => 'Vendors',
                'countLabelEn' => 'vendors',
                'icon' => 'fa-building',
                'href' => locale_route('admin.vendors.index'),
                'stats' => [
                    ['value' => (int) ($vendorProductCount ?? 0), 'labelDe' => 'Products', 'labelEn' => 'products'],
                ],
            ];
            $tiles[] = [
                'titleDe' => 'Sources',
                'titleEn' => 'Sources',
                'descDe' => 'Supplier Library — Produkte und Domains.',
                'descEn' => 'Supplier library — products and domains.',
                'count' => (int) ($supplierCount ?? 0),
                'countLabelDe' => 'Products',
                'countLabelEn' => 'products',
                'icon' => 'fa-database',
                'href' => locale_route('admin.suppliers.index'),
                'stats' => [],
            ];
        }
        if ($canArea('glossary')) {
            $tiles[] = [
                'titleDe' => 'Glossary',
                'titleEn' => 'Glossary',
                'descDe' => 'Gemeinsames Vokabular — Core und Buzzwords.',
                'descEn' => 'Shared vocabulary — core and buzzwords.',
                'count' => (int) ($glossaryCount ?? 0),
                'countLabelDe' => 'Terms',
                'countLabelEn' => 'terms',
                'icon' => 'fa-book',
                'href' => locale_route('admin.glossary.index'),
                'stats' => [
                    ['value' => (int) ($glossaryCoreCount ?? 0), 'labelDe' => 'Core', 'labelEn' => 'core'],
                    ['value' => (int) ($glossaryBuzzCount ?? 0), 'labelDe' => 'Buzzwords', 'labelEn' => 'buzzwords'],
                ],
            ];
        }
        if (! empty($canManageUsers)) {
            $tiles[] = [
                'titleDe' => 'Users',
                'titleEn' => 'Users',
                'descDe' => 'Accounts, Rollen und Freigaben.',
                'descEn' => 'Accounts, roles, and access.',
                'count' => (int) ($userCount ?? 0),
                'countLabelDe' => 'Users',
                'countLabelEn' => 'users',
                'icon' => 'fa-users',
                'href' => locale_route('admin.users.index'),
                'stats' => [
                    ['value' => (int) ($activeUserCount ?? 0), 'labelDe' => 'Aktiv', 'labelEn' => 'active'],
                    ['value' => (int) ($contentManagerCount ?? 0), 'labelDe' => 'Content', 'labelEn' => 'content'],
                ],
            ];
            $tiles[] = [
                'titleDe' => 'Story access',
                'titleEn' => 'Story access',
                'descDe' => 'ACL und Sichtbarkeit für Playbooks.',
                'descEn' => 'ACL and visibility for playbooks.',
                'count' => null,
                'countLabelDe' => '',
                'countLabelEn' => '',
                'icon' => 'fa-lock',
                'href' => locale_route('admin.story-acl.index'),
                'stats' => [],
            ];
            $linkStatus = (string) ($linkCheckStatus ?? 'none');
            $linkCheckedAt = is_string($linkCheckCheckedAt ?? null) ? $linkCheckCheckedAt : null;
            $linkIssues = (int) ($linkCheckIssues ?? 0);
            $linkOk = (int) ($linkCheckOk ?? 0);
            $linkBroken = (int) ($linkCheckBroken ?? 0);
            $linkError = (int) ($linkCheckError ?? 0);
            $linkTotal = (int) ($linkCheckTotal ?? 0);
            $linkDescDe = $linkStatus === 'running'
                ? 'Scan läuft im Hintergrund…'
                : ($linkCheckedAt
                    ? 'Zuletzt '.$linkCheckedAt.' — Broken Links in Content und Katalogen.'
                    : 'Noch kein Scan — Broken Links in Content und Katalogen prüfen.');
            $linkDescEn = $linkStatus === 'running'
                ? 'Scan running in the background…'
                : ($linkCheckedAt
                    ? 'Last '.$linkCheckedAt.' — broken links in content and catalogs.'
                    : 'No scan yet — check broken links in content and catalogs.');
            $linkStats = [];
            if ($linkCheckedAt !== null || $linkTotal > 0) {
                $linkStats[] = ['value' => $linkOk, 'labelDe' => 'OK', 'labelEn' => 'ok'];
                $linkStats[] = ['value' => $linkBroken, 'labelDe' => 'Broken', 'labelEn' => 'broken'];
                $linkStats[] = ['value' => $linkError, 'labelDe' => 'Errors', 'labelEn' => 'errors'];
            }
            $tiles[] = [
                'titleDe' => 'Link-Checker',
                'titleEn' => 'Link checker',
                'descDe' => $linkDescDe,
                'descEn' => $linkDescEn,
                'count' => $linkCheckedAt !== null || $linkTotal > 0 ? $linkIssues : null,
                'countLabelDe' => 'Probleme',
                'countLabelEn' => 'issues',
                'icon' => 'fa-link',
                'href' => locale_route('admin.link-check.index'),
                'stats' => $linkStats,
            ];
        }
        if (! empty($canManageTeams)) {
            $tiles[] = [
                'titleDe' => 'Teams',
                'titleEn' => 'Teams',
                'descDe' => 'Arbeitsbereiche und Team-Zuordnung.',
                'descEn' => 'Workspaces and team assignment.',
                'count' => (int) ($teamCount ?? 0),
                'countLabelDe' => 'Teams',
                'countLabelEn' => 'teams',
                'icon' => 'fa-people-group',
                'href' => locale_route('admin.teams.index'),
                'stats' => [],
            ];
        }
    @endphp
    {{-- Reuse landing Hubs band: tools-home + section-band-art (white line artwork). --}}
    <div class="tools-home admin-hub admin-hub--dashboard">
        <div class="tools-content">
            <section class="tools-section tools-section--band admin-hub__dashboard" aria-label="Dashboard">
                <x-tools.section-band-art />
                <div class="tools-section__body">
                    <header class="tools-section__head">
                        <h1
                            class="tools-section__title"
                            data-text-de="Dashboard"
                            data-text-en="Dashboard"
                        >Dashboard</h1>
                        <p
                            class="tools-section__lead"
                            data-text-de="Content, Kataloge und Accounts — mit aktuellen Beständen."
                            data-text-en="Content, catalogs, and accounts — with live counts."
                        >Content, catalogs, and accounts — with live counts.</p>
                    </header>
                    <div class="tools-card-grid">
                        @forelse ($tiles as $tile)
                            <a href="{{ $tile['href'] }}" class="tools-card tools-card--hub tools-card--hub-primary">
                                <div class="tools-card__main">
                                    <div class="tools-card__icon-wrap tools-card__icon-wrap--primary" aria-hidden="true">
                                        <i class="fa-solid {{ $tile['icon'] }} tools-card__icon"></i>
                                    </div>
                                    <div class="tools-card__body">
                                        <div class="tools-card__title-row">
                                            <h3
                                                class="tools-card__title"
                                                data-text-de="{{ $tile['titleDe'] }}"
                                                data-text-en="{{ $tile['titleEn'] }}"
                                            >{{ $tile['titleEn'] }}</h3>
                                        </div>
                                        @if ($tile['count'] !== null)
                                            <p class="tools-card__meta tools-card__meta--kpi">
                                                <span class="tools-card__count">{{ $tile['count'] }}</span>
                                                <span
                                                    data-text-de="{{ $tile['countLabelDe'] }}"
                                                    data-text-en="{{ $tile['countLabelEn'] }}"
                                                >{{ $tile['countLabelEn'] }}</span>
                                            </p>
                                        @endif
                                        <p
                                            class="tools-card__desc"
                                            data-text-de="{{ $tile['descDe'] }}"
                                            data-text-en="{{ $tile['descEn'] }}"
                                        >{{ $tile['descEn'] }}</p>
                                        @if (! empty($tile['stats']))
                                            <ul class="admin-hub__dashboard-stats">
                                                @foreach ($tile['stats'] as $stat)
                                                    <li>
                                                        <strong>{{ $stat['value'] }}</strong>
                                                        <span
                                                            data-text-de="{{ $stat['labelDe'] }}"
                                                            data-text-en="{{ $stat['labelEn'] }}"
                                                        >{{ $stat['labelEn'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
                                </div>
                            </a>
                        @empty
                            <p class="admin-hub__meta" data-text-de="Keine Bereiche freigeschaltet." data-text-en="No areas available.">No areas available.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
