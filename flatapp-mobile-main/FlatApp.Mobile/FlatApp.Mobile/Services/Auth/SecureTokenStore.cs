namespace FlatApp.Mobile.Services.Auth;

public sealed class SecureTokenStore : ITokenStore
{
    private const string TokenKey = "flatapp.auth_token";

    public async Task<string?> GetTokenAsync(CancellationToken cancellationToken = default)
    {
        cancellationToken.ThrowIfCancellationRequested();

        try
        {
            return await SecureStorage.Default.GetAsync(TokenKey);
        }
        catch (Exception)
        {
            await ClearTokenAsync(cancellationToken);
            return null;
        }
    }

    public async Task SaveTokenAsync(string token, CancellationToken cancellationToken = default)
    {
        cancellationToken.ThrowIfCancellationRequested();

        await SecureStorage.Default.SetAsync(TokenKey, token);
    }

    public Task ClearTokenAsync(CancellationToken cancellationToken = default)
    {
        cancellationToken.ThrowIfCancellationRequested();

        try
        {
            SecureStorage.Default.Remove(TokenKey);
        }
        catch (Exception)
        {
        }

        return Task.CompletedTask;
    }
}
