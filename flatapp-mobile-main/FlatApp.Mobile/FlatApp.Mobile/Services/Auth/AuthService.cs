using FlatApp.Mobile.Models.Auth;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Auth;

public sealed class AuthService(IApiClient apiClient, ITokenStore tokenStore) : IAuthService
{
    public async Task<AuthResponse?> LoginAsync(string email, string password, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<LoginRequest, AuthResponse>(
            "auth/login",
            new LoginRequest(email, password, DeviceInfo.Current.Name),
            cancellationToken);

        if (!string.IsNullOrWhiteSpace(response?.Token))
        {
            await tokenStore.SaveTokenAsync(response.Token, cancellationToken);
        }

        return response;
    }

    public async Task<AuthResponse?> RegisterAsync(string name, string email, string password, string passwordConfirmation, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<RegisterRequest, AuthResponse>(
            "auth/register",
            new RegisterRequest(name, email, password, passwordConfirmation, DeviceInfo.Current.Name),
            cancellationToken);

        if (!string.IsNullOrWhiteSpace(response?.Token))
        {
            await tokenStore.SaveTokenAsync(response.Token, cancellationToken);
        }

        return response;
    }

    public Task<AuthMeResponse?> GetMeAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<AuthMeResponse>("auth/me", cancellationToken);
    }

    public Task<AccountDeletionRequestStatusResponse?> GetAccountDeletionStatusAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<AccountDeletionRequestStatusResponse>("auth/account-deletion", cancellationToken);
    }

    public Task<AccountDeletionRequestCreateResponse?> RequestAccountDeletionAsync(string? reason, CancellationToken cancellationToken = default)
    {
        return apiClient.PostAsync<AccountDeletionRequestCreateRequest, AccountDeletionRequestCreateResponse>(
            "auth/account-deletion",
            new AccountDeletionRequestCreateRequest(reason),
            cancellationToken);
    }

    public async Task LogoutAsync(CancellationToken cancellationToken = default)
    {
        try
        {
            await apiClient.PostAsync<object, object>("auth/logout", new { }, cancellationToken);
        }
        finally
        {
            await tokenStore.ClearTokenAsync(cancellationToken);
        }
    }

    public async Task<bool> IsAuthenticatedAsync(CancellationToken cancellationToken = default)
    {
        var token = await tokenStore.GetTokenAsync(cancellationToken);

        return !string.IsNullOrWhiteSpace(token);
    }
}
