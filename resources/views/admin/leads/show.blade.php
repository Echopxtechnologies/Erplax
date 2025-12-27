{{-- <x-layouts.app> --}}
    <div style="padding: 20px; max-width: 1400px; margin: 0 auto;">
        <!-- Header with Back Link -->
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.leads.index') }}" style="color: #3498db; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                ← Back to Leads
            </a>
        </div>

        <!-- Main Card -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
            
            <!-- Header Section -->
            <div style="padding: 25px 30px; border-bottom: 1px solid #E3E6F0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0 0 5px 0; font-size: 28px; font-weight: 600;">{{ $lead->name }}</h1>
                    <span style="display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; color: white; background: {{ $lead->leadStatus->color ?? '#3498db' }};">
                        {{ $lead->leadStatus->name ?? 'No Status' }}
                    </span>
                </div>
                <div>
    <form action="{{ route('admin.leads.convert', $lead->id) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" onclick="return confirm('Are you sure you want to convert this lead to a customer?');" style="background: #27ae60; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Convert to Customer
        </button>
    </form>
</div>
            </div>

            <!-- Content Section - Two Columns -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 30px;">
                
                <!-- Left Column: Lead Information -->
                <div>
                    <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Lead Information</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">NAME</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->name }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">POSITION</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->title ?? '-' }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">EMAIL ADDRESS</label>
                            <p style="margin: 0; font-size: 15px;">
                                @if($lead->email)
                                    <a href="mailto:{{ $lead->email }}" style="color: #3498db; text-decoration: none;">{{ $lead->email }}</a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">WEBSITE</label>
                            <p style="margin: 0; font-size: 15px;">
                                @if($lead->website)
                                    <a href="{{ $lead->website }}" target="_blank" style="color: #3498db; text-decoration: none;">{{ $lead->website }}</a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">PHONE</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">
                                @if($lead->phonenumber)
                                    <a href="tel:{{ $lead->phonenumber }}" style="color: #2c3e50; text-decoration: none;">{{ $lead->phonenumber }}</a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">LEAD VALUE</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50; font-weight: 600;">
                                {{ $lead->lead_value > 0 ? '₹' . number_format($lead->lead_value, 2) : '-' }}
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">COMPANY</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->company ?? '-' }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">ADDRESS</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50; line-height: 1.6;">
                                @if($lead->address || $lead->city || $lead->state || $lead->country || $lead->zip)
                                    {{ $lead->address }}<br>
                                    {{ $lead->city }}{{ $lead->state ? ', ' . $lead->state : '' }} {{ $lead->zip }}<br>
                                    {{ $lead->country }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: General Information -->
                <div>
                    <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">General Information</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">STATUS</label>
                            <p style="margin: 0;">
                                <span style="display: inline-block; padding: 6px 14px; border-radius: 15px; font-size: 13px; font-weight: 600; color: white; background: {{ $lead->leadStatus->color ?? '#3498db' }};">
                                    {{ $lead->leadStatus->name ?? 'No Status' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">SOURCE</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->leadSource->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">ASSIGNED TO</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->assignedUser->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">CREATED</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">LAST UPDATED</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50;">{{ $lead->updated_at->format('M d, Y H:i') }}</p>
                        </div>

                        

                        @if($lead->description)
                        <div>
                            <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px; font-weight: 600;">DESCRIPTION</label>
                            <p style="margin: 0; font-size: 15px; color: #2c3e50; line-height: 1.6;">{{ $lead->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    
{{-- </x-layouts.app> --}}
