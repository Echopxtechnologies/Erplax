<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Dashboard</h1>
    </x-slot>

    {{-- Welcome Section --}}
    <div style="margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px;">
            Welcome back, {{ auth('admin')->user()->name ?? 'Admin' }}! 👋
        </h2>
        <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Here's what's happening with your business today.</p>
    </div>

    {{-- Stats Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
        {{-- Revenue --}}
        <div class="card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--success-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="badge badge-success">+12.5%</span>
            </div>
            <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Total Revenue</p>
            <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 4px 0 0;">$0.00</p>
        </div>

        {{-- Customers --}}
        <div class="card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="badge badge-info">+8.2%</span>
            </div>
            <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Total Customers</p>
            <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 4px 0 0;">0</p>
        </div>

        {{-- Invoices --}}
        <div class="card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--warning-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="badge badge-warning">Pending</span>
            </div>
            <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Pending Invoices</p>
            <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 4px 0 0;">0</p>
        </div>

        {{-- Tickets --}}
        <div class="card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--danger-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="badge badge-danger">Open</span>
            </div>
            <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Open Tickets</p>
            <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 4px 0 0;">0</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0 0 12px;">Quick Actions</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
            <button class="card" style="padding: 16px; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">New Customer</span>
            </button>

            <button class="card" style="padding: 16px; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--success-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">New Invoice</span>
            </button>

            <button class="card" style="padding: 16px; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #f3e8ff; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: #9333ea;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">New Project</span>
            </button>

            <button class="card" style="padding: 16px; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--warning-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">New Ticket</span>
            </button>
        </div>
    </div>

    {{-- Tables Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
        {{-- Recent Invoices --}}
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px;">
                <h3 class="card-title">Recent Invoices</h3>
                <a href="#" style="font-size: 12px; font-weight: 500; color: var(--primary); text-decoration: none;">View all →</a>
            </div>
            <div class="card-body" style="padding: 24px 16px;">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--body-bg); display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg style="width: 24px; height: 24px; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p style="font-size: 13px; font-weight: 500; color: var(--text-secondary); margin: 0;">No invoices yet</p>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">Create your first invoice to get started</p>
                </div>
            </div>
        </div>

        {{-- Recent Customers --}}
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px;">
                <h3 class="card-title">Recent Customers</h3>
                <a href="#" style="font-size: 12px; font-weight: 500; color: var(--primary); text-decoration: none;">View all →</a>
            </div>
            <div class="card-body" style="padding: 24px 16px;">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--body-bg); display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg style="width: 24px; height: 24px; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p style="font-size: 13px; font-weight: 500; color: var(--text-secondary); margin: 0;">No customers yet</p>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">Add your first customer to get started</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>