using FlatApp.Mobile.Models.Flats;

namespace FlatApp.Mobile.Services.Flats;

public interface IFlatService
{
    Task<CurrentFlatResponse?> GetCurrentAsync(CancellationToken cancellationToken = default);

    Task<string?> GetCurrentInviteCodeAsync(CancellationToken cancellationToken = default);

    Task<IReadOnlyList<FlatMember>> GetMembersAsync(CancellationToken cancellationToken = default);

    Task<string?> UpdateMemberRoleAsync(int userId, string role, CancellationToken cancellationToken = default);

    Task<string?> RemoveMemberAsync(int userId, CancellationToken cancellationToken = default);

    Task<IReadOnlyList<FlatJoinRequest>> GetJoinRequestsAsync(CancellationToken cancellationToken = default);

    Task<string?> ApproveJoinRequestAsync(int id, CancellationToken cancellationToken = default);

    Task<string?> RejectJoinRequestAsync(int id, CancellationToken cancellationToken = default);

    Task<CurrentFlatResponse?> SetCurrentAsync(int flatId, CancellationToken cancellationToken = default);

    Task<CurrentFlatResponse?> CreateFlatAsync(string name, string? address, CancellationToken cancellationToken = default);

    Task<JoinFlatResponse?> JoinFlatAsync(string inviteCode, CancellationToken cancellationToken = default);
}
