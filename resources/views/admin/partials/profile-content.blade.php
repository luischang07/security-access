<div class="profile-container">
  <div class="profile-header">
    <h2>{{ __('admin.profile.title') }}</h2>
    <p>{{ __('admin.profile.subtitle') }}</p>
  </div>

  <div class="profile-card">
    <div class="profile-avatar">
      <div class="avatar-circle">
        {{ strtoupper(substr($user->nombre, 0, 1) . substr($user->apellido, 0, 1)) }}
      </div>
    </div>

    <div class="profile-info">
      <div class="info-group">
        <label>{{ __('admin.profile.full_name') }}</label>
        <p>{{ $user->nombre }} {{ $user->apellido }}</p>
      </div>

      <div class="info-group">
        <label>{{ __('admin.profile.email') }}</label>
        <p>{{ $user->correo }}</p>
      </div>

      <div class="info-group">
        <label>{{ __('admin.profile.role') }}</label>
        <p class="badge badge-admin">{{ __('admin.roles.admin') }}</p>
      </div>

      <div class="info-group">
        <label>{{ __('admin.profile.joined_at') }}</label>
        <p>{{ $user->created_at->format('d M, Y') }}</p>
      </div>
    </div>
  </div>
</div>

<style>
  .profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
  }

  .profile-header {
    margin-bottom: 2rem;
    text-align: center;
  }

  .profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
  }

  .avatar-circle {
    width: 100px;
    height: 100px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: bold;
  }

  .profile-info {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }

  .info-group label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
  }

  .info-group p {
    font-size: 1.125rem;
    color: #111827;
    font-weight: 500;
  }

  .badge-admin {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #dbeafe;
    color: #1e40af;
    border-radius: 9999px;
    font-size: 0.875rem;
  }
</style>