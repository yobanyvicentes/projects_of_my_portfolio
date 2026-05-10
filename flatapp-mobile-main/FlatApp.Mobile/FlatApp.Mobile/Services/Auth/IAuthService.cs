using FlatApp.Mobile.Models.Auth;

namespace FlatApp.Mobile.Services.Auth;

public interface IAuthService
{
    Task<AuthResponse?> LoginAsync(string email, string password, CancellationToken cancellationToken = default);

    Task<AuthResponse?> RegisterAsync(string name, string email, string password, string passwordConfirmation, CancellationToken cancellationToken = default);

    Task<AuthMeResponse?> GetMeAsync(CancellationToken cancellationToken = default);

    Task<AccountDeletionRequestStatusResponse?> GetAccountDeletionStatusAsync(CancellationToken cancellationToken = default);

    Task<AccountDeletionRequestCreateResponse?> RequestAccountDeletionAsync(string? reason, CancellationToken cancellationToken = default);

    Task LogoutAsync(CancellationToken cancellationToken = default);

    Task<bool> IsAuthenticatedAsync(CancellationToken cancellationToken = default);
}
