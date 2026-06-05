@extends('store.layouts.app')

@section('title', 'Tools - NumNam')
@section('meta_description', 'Essential tools for your baby\'s nutrition journey - trackers, recipes, guides, and more.')

@section('content')
<div class="tools-container">
    <div class="tools-hero">
        <h1 class="tools-title">Baby Care Tools</h1>
        <p class="tools-subtitle">Expert-crafted tools to guide every stage of your baby's nutrition journey</p>
    </div>

    <div class="tools-grid">
        @foreach($tools as $tool)
        <div class="tool-card">
            <div class="tool-icon">{{ $tool['icon'] }}</div>
            <h3 class="tool-name">{{ $tool['name'] }}</h3>
            <p class="tool-description">{{ $tool['description'] }}</p>
            <span class="tool-category">{{ $tool['category'] }}</span>
            <a href="{{ route($tool['route']) }}" class="tool-cta">
                Open Tool
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        @endforeach
    </div>
</div>

<style>
    .tools-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .tools-hero {
        text-align: center;
        margin-bottom: 60px;
    }

    .tools-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .tools-subtitle {
        font-size: 1.1rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 28px;
        margin-bottom: 40px;
    }

    .tool-card {
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: 16px;
        padding: 32px 24px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .tool-card:hover {
        border-color: #FF6B8A;
        box-shadow: 0 12px 32px rgba(255, 107, 138, 0.12);
        transform: translateY(-4px);
    }

    .tool-icon {
        font-size: 3.5rem;
        margin-bottom: 16px;
    }

    .tool-name {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .tool-description {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 16px;
        flex-grow: 1;
    }

    .tool-category {
        display: inline-block;
        background: #FFF3F5;
        color: #FF6B8A;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .tool-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #FF6B8A 0%, #FF5A7A 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .tool-cta:hover {
        transform: translateX(4px);
        box-shadow: 0 6px 16px rgba(255, 107, 138, 0.3);
    }

    @media (max-width: 640px) {
        .tools-title {
            font-size: 1.8rem;
        }

        .tools-subtitle {
            font-size: 0.95rem;
        }

        .tools-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>
@endsection