using FlatApp.Mobile.Models.Activity;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Activity;

public sealed class ActivityService(IApiClient apiClient) : IActivityService
{
    public async Task<IReadOnlyList<ActivityItem>> GetAsync(CancellationToken cancellationToken = default)
    {
        var response = await apiClient.GetAsync<ActivityResponse>("activity", cancellationToken);

        return response?.Activities ?? [];
    }
}
