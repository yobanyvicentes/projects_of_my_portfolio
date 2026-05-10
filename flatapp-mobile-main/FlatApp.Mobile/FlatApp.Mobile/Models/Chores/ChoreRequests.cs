namespace FlatApp.Mobile.Models.Chores;

public sealed record CreateChoreRequest(
    string Title,
    string? Description,
    string Frequency,
    string Effort,
    string? StartDate,
    IReadOnlyList<int> Members);

public sealed record UpdateChoreRequest(
    string Title,
    string? Description,
    string Frequency,
    string Effort,
    string? StartDate,
    string? NextDueDate,
    IReadOnlyList<int> Members);
