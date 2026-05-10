namespace FlatApp.Mobile.Models.Activity;

public sealed record ActivityItem(
    int Id,
    string Action,
    string? Description,
    int? UserId,
    string? UserName,
    DateTimeOffset? CreatedAt);

public sealed record ActivityResponse(IReadOnlyList<ActivityItem> Activities);
