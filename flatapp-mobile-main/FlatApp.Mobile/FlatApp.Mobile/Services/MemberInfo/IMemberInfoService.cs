using FlatApp.Mobile.Models.Profile;

namespace FlatApp.Mobile.Services.MemberInfo;

public interface IMemberInfoService
{
    Task<MemberProfile?> GetAsync(CancellationToken cancellationToken = default);

    Task<UpdateMemberProfileResponse?> UpdateAsync(UpdateMemberProfileRequest request, CancellationToken cancellationToken = default);
}
