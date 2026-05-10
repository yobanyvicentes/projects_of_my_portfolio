using FlatApp.Mobile.Models.Activity;

namespace FlatApp.Mobile.Services.Activity;

public interface IActivityService
{
    Task<IReadOnlyList<ActivityItem>> GetAsync(CancellationToken cancellationToken = default);
}
