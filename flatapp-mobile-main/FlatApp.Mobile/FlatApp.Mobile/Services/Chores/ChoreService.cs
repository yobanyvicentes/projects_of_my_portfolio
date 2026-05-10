using FlatApp.Mobile.Models.Chores;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Chores;

public sealed class ChoreService(IApiClient apiClient) : IChoreService
{
    public Task<ChoresResponse?> GetAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<ChoresResponse>("chores", cancellationToken);
    }

    public async Task<IReadOnlyList<ChoreItem>> GetChoresAsync(CancellationToken cancellationToken = default)
    {
        var response = await GetAsync(cancellationToken);

        return response?.Chores ?? [];
    }

    public async Task<ChoreItem?> CreateAsync(CreateChoreRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<CreateChoreRequest, ChoreResponse>("chores", request, cancellationToken);

        return response?.Chore;
    }

    public async Task<ChoreItem?> UpdateAsync(int id, UpdateChoreRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PutAsync<UpdateChoreRequest, ChoreResponse>($"chores/{id}", request, cancellationToken);

        return response?.Chore;
    }

    public async Task<ChoreItem?> CompleteAsync(int id, int completedBy, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<CompleteChoreRequest, ChoreResponse>(
            $"chores/{id}/complete",
            new CompleteChoreRequest(completedBy),
            cancellationToken);

        return response?.Chore;
    }

    public Task ArchiveAsync(int id, CancellationToken cancellationToken = default)
    {
        return apiClient.DeleteAsync($"chores/{id}", cancellationToken);
    }
}
