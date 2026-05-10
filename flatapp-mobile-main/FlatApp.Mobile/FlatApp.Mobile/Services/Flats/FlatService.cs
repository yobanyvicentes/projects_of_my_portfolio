using System.Text.Json;
using FlatApp.Mobile.Models.Flats;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Flats;

public sealed class FlatService(IApiClient apiClient) : IFlatService
{
    public Task<CurrentFlatResponse?> GetCurrentAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<CurrentFlatResponse>("flats/current", cancellationToken);
    }

    public async Task<string?> GetCurrentInviteCodeAsync(CancellationToken cancellationToken = default)
    {
        var response = await apiClient.GetAsync<JsonElement>("flats/current", cancellationToken);

        if (!response.TryGetProperty("currentFlat", out var currentFlat) || currentFlat.ValueKind is JsonValueKind.Null)
        {
            return null;
        }

        if (!currentFlat.TryGetProperty("inviteCode", out var inviteCode) || inviteCode.ValueKind is not JsonValueKind.String)
        {
            return null;
        }

        return inviteCode.GetString();
    }

    public async Task<IReadOnlyList<FlatMember>> GetMembersAsync(CancellationToken cancellationToken = default)
    {
        var response = await apiClient.GetAsync<CurrentFlatMembersResponse>("flats/current/members", cancellationToken);

        return response?.Members ?? [];
    }

    public async Task<string?> UpdateMemberRoleAsync(int userId, string role, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PutAsync<UpdateFlatMemberRoleRequest, FlatMemberActionResponse>(
            $"flats/current/members/{userId}",
            new UpdateFlatMemberRoleRequest(role),
            cancellationToken);

        return response?.Message;
    }

    public async Task<string?> RemoveMemberAsync(int userId, CancellationToken cancellationToken = default)
    {
        await apiClient.DeleteAsync($"flats/current/members/{userId}", cancellationToken);

        return "Member removed.";
    }

    public async Task<IReadOnlyList<FlatJoinRequest>> GetJoinRequestsAsync(CancellationToken cancellationToken = default)
    {
        var response = await apiClient.GetAsync<FlatJoinRequestsResponse>("flats/current/join-requests", cancellationToken);

        return response?.JoinRequests ?? [];
    }

    public async Task<string?> ApproveJoinRequestAsync(int id, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<object, FlatJoinRequestActionResponse>(
            $"flats/current/join-requests/{id}/approve",
            new { },
            cancellationToken);

        return response?.Message;
    }

    public async Task<string?> RejectJoinRequestAsync(int id, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<object, FlatJoinRequestActionResponse>(
            $"flats/current/join-requests/{id}/reject",
            new { },
            cancellationToken);

        return response?.Message;
    }

    public Task<CurrentFlatResponse?> SetCurrentAsync(int flatId, CancellationToken cancellationToken = default)
    {
        return apiClient.PostAsync<SwitchCurrentFlatRequest, CurrentFlatResponse>(
            "flats/current",
            new SwitchCurrentFlatRequest(flatId),
            cancellationToken);
    }

    public Task<CurrentFlatResponse?> CreateFlatAsync(string name, string? address, CancellationToken cancellationToken = default)
    {
        return apiClient.PostAsync<CreateFlatRequest, CurrentFlatResponse>(
            "flats",
            new CreateFlatRequest(name, address),
            cancellationToken);
    }

    public Task<JoinFlatResponse?> JoinFlatAsync(string inviteCode, CancellationToken cancellationToken = default)
    {
        return apiClient.PostAsync<object, JoinFlatResponse>(
            "flats/join",
            new { invite_code = inviteCode },
            cancellationToken);
    }
}
