namespace FlatApp.Mobile.Models.Flats;

public sealed record FlatSummary(int Id, string Name, string? Address, string? PhotoUrl);

public sealed record CurrentFlatResponse(FlatSummary? CurrentFlat, IReadOnlyList<FlatSummary> Flats);

public sealed record SwitchCurrentFlatRequest(int FlatId);

public sealed record CreateFlatRequest(string Name, string? Address);

public sealed record JoinFlatRequest(string InviteCode);

public sealed record JoinFlatResponse(string Status, string Message);

public sealed record CurrentFlatMembersResponse(IReadOnlyList<FlatMember> Members);

public sealed record FlatMember(int Id, string Name, string Email, string Role);
