  @extends('front.layouts.app')
  @section('main')
  <!-- Hero Banner Section -->
  <section class="hero-banner d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 600px; position: relative; overflow: hidden;">
    <!-- Animated Background Elements -->
    <div class="hero-bg-shapes">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
      <div class="shape shape-3"></div>
    </div>
    
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 text-white mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">Find Your Dream Job Today</h1>
                <p class="lead mb-4 fs-5">Discover thousands of job opportunities from top companies. Start your career journey with us.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('jobs') }}" class="btn btn-light btn-lg px-5 fw-bold">Explore Jobs</a>
                    <a href="#featured" class="btn btn-outline-light btn-lg px-5 fw-bold">Learn More</a>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="hero-image-container">
                    <svg viewBox="0 0 400 400" class="hero-svg" xmlns="http://www.w3.org/2000/svg">
                        <!-- Job Search Illustration -->
                        <circle cx="200" cy="200" r="180" fill="rgba(255,255,255,0.1)" stroke="white" stroke-width="2"/>
                        <rect x="120" y="100" width="160" height="200" rx="10" fill="white" opacity="0.9"/>
                        <rect x="130" y="110" width="140" height="30" fill="#667eea"/>
                        <circle cx="160" cy="160" r="15" fill="#764ba2"/>
                        <rect x="130" y="190" width="140" height="8" fill="#e0e0e0"/>
                        <rect x="130" y="210" width="140" height="8" fill="#e0e0e0"/>
                        <rect x="130" y="230" width="100" height="8" fill="#e0e0e0"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
  </section>

<section class="search-section py-5" style="background: #f8f9fa; margin-top: -50px; position: relative; z-index: 10;">
    <div class="container">
        <div class="card border-0 shadow-lg p-5" style="border-radius: 15px;">
            <h3 class="mb-4 text-center fw-bold">Find Your Perfect Job</h3>
            <form action="{{ route("jobs") }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-lg" name="keyword" id="keyword" placeholder="Job title or keyword" style="border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-lg" name="location" id="location" placeholder="City or location" style="border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                    <div class="col-md-3">
                        <select name="category" id="category" class="form-control form-control-lg" style="border-radius: 8px; border: 2px solid #e0e0e0;">
                            <option value="">All Categories</option>
                           @if ($newCategories->isNotEmpty())
                           @foreach ( $newCategories as  $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                           @endforeach
                           @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fa fa-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="categories-section py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="font-size: 2.5rem;">Popular Categories</h2>
            <p class="text-muted fs-5">Explore jobs across different industries</p>
        </div>
        <div class="row g-4 pt-4">
            @if ($Categories->isNotEmpty())
            @foreach ($Categories as $category)
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('jobs') . '?category=' . $category->id }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center h-100" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer;">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #667eea;">
                                @php
                                    $categoryName = strtolower($category->name);
                                    $icon = 'fa-briefcase';
                                    
                                    if (strpos($categoryName, 'it') !== false || strpos($categoryName, 'tech') !== false || strpos($categoryName, 'software') !== false || strpos($categoryName, 'developer') !== false) {
                                        $icon = 'fa-laptop';
                                    } elseif (strpos($categoryName, 'design') !== false || strpos($categoryName, 'graphic') !== false) {
                                        $icon = 'fa-paint-brush';
                                    } elseif (strpos($categoryName, 'marketing') !== false || strpos($categoryName, 'sales') !== false) {
                                        $icon = 'fa-bullhorn';
                                    } elseif (strpos($categoryName, 'finance') !== false || strpos($categoryName, 'accounting') !== false || strpos($categoryName, 'bank') !== false) {
                                        $icon = 'fa-money';
                                    } elseif (strpos($categoryName, 'construction') !== false) {
                                        $icon = 'fa-wrench';
                                    } elseif (strpos($categoryName, 'engineer') !== false) {
                                        $icon = 'fa-cog';
                                    } elseif (strpos($categoryName, 'social') !== false || strpos($categoryName, 'media') !== false) {
                                        $icon = 'fa-share-alt';
                                    } elseif (strpos($categoryName, 'trading') !== false || strpos($categoryName, 'commerce') !== false) {
                                        $icon = 'fa-line-chart';
                                    } elseif (strpos($categoryName, 'health') !== false || strpos($categoryName, 'medical') !== false || strpos($categoryName, 'nurse') !== false) {
                                        $icon = 'fa-heartbeat';
                                    } elseif (strpos($categoryName, 'education') !== false || strpos($categoryName, 'teacher') !== false) {
                                        $icon = 'fa-graduation-cap';
                                    } elseif (strpos($categoryName, 'hr') !== false || strpos($categoryName, 'human') !== false) {
                                        $icon = 'fa-users';
                                    } elseif (strpos($categoryName, 'legal') !== false || strpos($categoryName, 'law') !== false) {
                                        $icon = 'fa-gavel';
                                    } elseif (strpos($categoryName, 'hospitality') !== false || strpos($categoryName, 'hotel') !== false || strpos($categoryName, 'restaurant') !== false) {
                                        $icon = 'fa-cutlery';
                                    }
                                @endphp
                                <i class="fa {{ $icon }}"></i>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: #333;">{{ $category->name }}</h4>
                            <p class="text-muted small">Explore opportunities</p>
                        </div>
                    </a>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

<section class="featured-jobs-section py-5" id="featured" style="background: white;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="font-size: 2.5rem;">Featured Jobs</h2>
            <p class="text-muted fs-5">Top opportunities from leading companies</p>
        </div>
        <div class="row g-4 pt-4">
            @if ($featuredJobs->isNotEmpty())
            @foreach ($featuredJobs as $featuredJob)
                <div class="col-md-4">
                    <div class="job-card h-100" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border-left: 5px solid #667eea;">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="fs-5 fw-bold mb-0" style="color: #333;">{{ $featuredJob->title }}</h3>
                                <span class="badge bg-success">Featured</span>
                            </div>
                            <p class="text-muted small mb-3">{{ Str::words($featuredJob->description, 8) }}</p>
                            
                            <div class="job-meta mb-4">
                                <p class="mb-2">
                                    <i class="fa fa-map-marker" style="color: #667eea;"></i>
                                    <span class="ms-2">{{ $featuredJob->location }}</span>
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-clock-o" style="color: #667eea;"></i>
                                    <span class="ms-2">{{ $featuredJob->jobType->name }}</span>
                                </p>
                                @if (!is_null($featuredJob->salary))
                                <p class="mb-0">
                                    <i class="fa fa-money" style="color: #667eea;"></i>
                                    <span class="ms-2 fw-bold">Rs. {{ number_format($featuredJob->salary) }}/month</span>
                                </p>
                                @endif
                            </div>

                            <a href="{{ route('jobDetail', $featuredJob->id) }}" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

<section class="latest-jobs-section py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="font-size: 2.5rem;">Latest Jobs</h2>
            <p class="text-muted fs-5">Recently posted opportunities</p>
        </div>
        <div class="row g-4 pt-4">
            @if ($latestJobs->isNotEmpty())
            @foreach ($latestJobs as $latestJob)
                <div class="col-md-4">
                    <div class="job-card h-100" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border-left: 5px solid #764ba2;">
                        <div class="p-4">
                            <h3 class="fs-5 fw-bold mb-3" style="color: #333;">{{ $latestJob->title }}</h3>
                            <p class="text-muted small mb-3">{{ Str::words($latestJob->description, 8) }}</p>
                            
                            <div class="job-meta mb-4">
                                <p class="mb-2">
                                    <i class="fa fa-map-marker" style="color: #764ba2;"></i>
                                    <span class="ms-2">{{ $latestJob->location }}</span>
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-clock-o" style="color: #764ba2;"></i>
                                    <span class="ms-2">{{ $latestJob->jobType->name }}</span>
                                </p>
                                @if (!is_null($latestJob->salary))
                                <p class="mb-0">
                                    <i class="fa fa-money" style="color: #764ba2;"></i>
                                    <span class="ms-2 fw-bold">Rs. {{ number_format((float) $latestJob->salary) }}/month</span>
                                </p>
                                @endif
                            </div>

                            <a href="{{ route('jobDetail', $latestJob->id) }}" class="btn btn-outline-primary w-100" style="border-radius: 8px; border: 2px solid #764ba2; color: #764ba2;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
  @endsection