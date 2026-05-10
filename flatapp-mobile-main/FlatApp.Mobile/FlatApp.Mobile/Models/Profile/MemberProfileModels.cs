namespace FlatApp.Mobile.Models.Profile;

public sealed record MemberProfileResponse(MemberProfile Profile);

public sealed record UpdateMemberProfileResponse(string Message, MemberProfile Profile);

public sealed record MemberProfile(
    int MembershipId,
    string Role,
    MemberProfileUser User,
    MemberProfileFlat Flat,
    string? Phone,
    string? EmergencyContactName,
    string? EmergencyContactPhone,
    string? BankAccountName,
    string? BankAccountNumber,
    string? Notes);

public sealed record MemberProfileUser(int Id, string Name, string Email);

public sealed record MemberProfileFlat(int Id, string Name, string? Address, bool CanManageHome);

public sealed record UpdateMemberProfileRequest(
    string? Phone,
    string? EmergencyContactName,
    string? EmergencyContactPhone,
    string? BankAccountName,
    string? BankAccountNumber,
    string? Notes,
    string? FlatName,
    string? FlatAddress);
