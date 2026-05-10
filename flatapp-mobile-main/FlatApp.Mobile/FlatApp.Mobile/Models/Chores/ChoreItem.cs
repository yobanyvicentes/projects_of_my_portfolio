namespace FlatApp.Mobile.Models.Chores;

public sealed record ChoresResponse(
    IReadOnlyList<ChoreItem> Chores,
    IReadOnlyList<ChoreLeaderboardItem>? Leaderboard = null);

public sealed record ChoreResponse(ChoreItem Chore, string? Message = null);

public sealed record ChoreItem(
    int Id,
    string Title,
    string? Description,
    string? Status,
    string Frequency,
    string Effort,
    int Points,
    string? StartDate,
    string? NextDueDate,
    int? AssignedTo,
    string? AssigneeName,
    IReadOnlyList<ChoreMember> Members);

public sealed record ChoreMember(int Id, string Name, string Email);

public sealed record ChoreLeaderboardItem(
    int UserId,
    string Name,
    string? Email,
    int TotalPoints,
    int CompletedCount);

public sealed record CompleteChoreRequest(int CompletedBy);
