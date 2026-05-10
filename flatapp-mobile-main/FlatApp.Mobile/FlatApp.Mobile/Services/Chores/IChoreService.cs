using FlatApp.Mobile.Models.Chores;

namespace FlatApp.Mobile.Services.Chores;

public interface IChoreService
{
    Task<ChoresResponse?> GetAsync(CancellationToken cancellationToken = default);

    Task<IReadOnlyList<ChoreItem>> GetChoresAsync(CancellationToken cancellationToken = default);

    Task<ChoreItem?> CreateAsync(CreateChoreRequest request, CancellationToken cancellationToken = default);

    Task<ChoreItem?> UpdateAsync(int id, UpdateChoreRequest request, CancellationToken cancellationToken = default);

    Task<ChoreItem?> CompleteAsync(int id, int completedBy, CancellationToken cancellationToken = default);

    Task ArchiveAsync(int id, CancellationToken cancellationToken = default);
}
