namespace FlatApp.Mobile.Models.Flats;

public sealed record FlatJoinRequest(
    int Id,
    int UserId,
    string UserName,
    string UserEmail,
    string Status,
    DateTimeOffset? CreatedAt);

public sealed record FlatJoinRequestsResponse(IReadOnlyList<FlatJoinRequest> JoinRequests);

public sealed record FlatJoinRequestActionResponse(string Message);
