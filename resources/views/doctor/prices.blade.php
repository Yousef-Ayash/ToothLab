@extends('layouts.app')

@section('content')
<div class="prices-page">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">مختبر البني</div>
        <div class="logo-subtext">للتعويضات السنية</div>
    </div>
    
    <h2 class="price-header">قائمة الأسعار ابتداءً من تاريخ 15/1/2025</h2>
    
    <div class="table-responsive">
        <table class="price-table">
            <thead>
                <tr>
                    <th>نوع العمل</th>
                    <th>السعر</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>خزف على معدن PFM (UNITE)</td>
                    <td class="price">7.5$</td>
                </tr>
                <tr>
                    <td>زيركون Regular (UNITE)</td>
                    <td class="price">15$</td>
                </tr>
                <tr>
                    <td>زيركون Multi-layer (UNITE)</td>
                    <td class="price">20$</td>
                </tr>
                <tr>
                    <td>زيركون Full Anatomy (UNITE)</td>
                    <td class="price">11$</td>
                </tr>
                <tr>
                    <td>E-max Veneer (UNITE)</td>
                    <td class="price">20$</td>
                </tr>
                <tr>
                    <td>PFM Over implant (UNITE)</td>
                    <td class="price">11$</td>
                </tr>
                <tr>
                    <td>Zirconia Over implant (UNITE)</td>
                    <td class="price">20-17$</td>
                </tr>
                <tr>
                    <td>Post & Core (UNITE)</td>
                    <td class="price">4$</td>
                </tr>
                <tr>
                    <td>Temporary PMMA (UNITE)</td>
                    <td class="price">5$</td>
                </tr>
                <tr>
                    <td>Temporary Resine (UNITE)</td>
                    <td class="price">4$</td>
                </tr>
                <tr>
                    <td>Implant Abutment Design (UNITE)</td>
                    <td class="price">13$</td>
                </tr>
                <tr>
                    <td>صفائح تبييض (ARCH2)</td>
                    <td class="price">6$</td>
                </tr>
                <tr>
                    <td>صفائح رفع عضة Night guard (ARCH1)</td>
                    <td class="price">5$</td>
                </tr>
                <tr>
                    <td>Wax-up (UNITE)</td>
                    <td class="price">2$</td>
                </tr>
                <tr>
                    <td>Try-in (UNITE)</td>
                    <td class="price">3$</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <p class="price-footer">للاستفسار عن التعويضات المتحركة والتقويم التواصل مع الإدارة عبر الأرقام التالية</p>
    
    <div class="contact-info">
        <p>دمشق - مدخل توريني - مقابل بناية الشمالي - دخلة براءم السنية</p>
        <p><i class="fab fa-whatsapp"></i> 0936 153 111 - 0982 072 746</p>
        <p><i class="fas fa-phone"></i> 011 8303990 - 8835885</p>
    </div>
</div>
@endsection
