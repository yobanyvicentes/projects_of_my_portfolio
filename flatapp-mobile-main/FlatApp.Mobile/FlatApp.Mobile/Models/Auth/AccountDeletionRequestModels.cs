namespace FlatApp.Mobile.Models.Auth;

public sealed record AccountDeletionRequestStatusResponse(AccountDeletionRequestItem? Request);

public sealed record AccountDeletionRequestCreateRequest(string? Reason);

public sealed record AccountDeletionRequestCreateResponse(string Message, AccountDeletionRequestItem Request);

public sealed record AccountDeletionRequestItem(
    int Id,
    string Status,
    string? Reason,
    string? RequestedAt,
    string? ProcessedAt,
    string? Notes);
