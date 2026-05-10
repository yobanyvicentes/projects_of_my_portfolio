namespace FlatApp.Mobile.Services.Auth;

public interface ITokenStore
{
    Task<string?> GetTokenAsync(CancellationToken cancellationToken = default);

    Task SaveTokenAsync(string token, CancellationToken cancellationToken = default);

    Task ClearTokenAsync(CancellationToken cancellationToken = default);
}
