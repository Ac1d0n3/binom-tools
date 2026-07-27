<p
    class="tools-section__lead"
    data-hub-lead
    data-text-de="Welche Entitäten laden, was skippen, wo PII sitzt — dann Source Scope und Readiness."
    data-text-en="Which entities to load, what to skip, where PII sits — then source scope and readiness."
>Which entities to load, what to skip, where PII sits — then source scope and readiness.</p>

<div class="governance-landing__supplier-cards">
    @foreach ($featuredSuppliers as $supplier)
        @php
            $id = (string) ($supplier['id'] ?? '');
            $nameEn = is_array($supplier['label'] ?? null)
                ? (string) ($supplier['label']['en'] ?? $id)
                : (string) ($supplier['label'] ?? $id);
            $nameDe = is_array($supplier['label'] ?? null)
                ? (string) ($supplier['label']['de'] ?? $nameEn)
                : $nameEn;
            $href = $id !== '' && \Illuminate\Support\Facades\Route::has('suppliers.show')
                ? locale_route('suppliers.show', ['slug' => $id])
                : locale_route('suppliers.index');
        @endphp
        <a class="governance-landing__supplier-card" href="{{ $href }}">
            <span class="governance-landing__supplier-card-title" data-text-de="{{ $nameDe }}" data-text-en="{{ $nameEn }}">{{ $nameEn }}</span>
            <span data-text-de="Kernobjekte, Skip, PII, Standard-KPIs" data-text-en="Core objects, skip, PII, standard KPIs">Core objects, skip, PII, standard KPIs</span>
        </a>
    @endforeach
</div>

<div class="governance-hub__hero-actions">
    <a class="governance-hub__button" href="{{ locale_route('suppliers.index') }}">
        <i class="fa-solid fa-database" aria-hidden="true"></i>
        <span data-text-de="Gesamte Supplier Library" data-text-en="Full supplier library">Full supplier library</span>
    </a>
</div>

<p class="governance-hub__soft-label" data-text-de="Passende Tools" data-text-en="Related tools">Related tools</p>
<x-governance.tool-cards :tools="$supplierRelatedTools" />
